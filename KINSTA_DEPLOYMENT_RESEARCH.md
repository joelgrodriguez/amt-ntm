# Kinsta deployment research for `amt-ntm`

Research date: 2026-07-11
Scope: deploying this repository's `master` branch to Kinsta Managed WordPress Hosting
Sources: official Kinsta and GitHub documentation only

Implementation note: this repository now uses the SFTP artifact alternative
described below. `.github/workflows/deploy-kinsta-staging.yml` builds
`app/dist/` in GitHub Actions, validates and packages `app/`, uploads it to the
`theme-update` Kinsta staging environment, keeps one previous release for
rollback, boots WordPress as a deploy check, purges caches, and smoke-tests the
staging URL. Build artifacts therefore do not need to be committed to `master`.

## Executive answer

Kinsta does not provide a native Git remote such as `git push kinsta`. It does provide Git, SSH, SFTP, WP-CLI, staging environments, backups, an API, and an official GitHub Actions deployment recipe. The official Actions pattern connects a GitHub-hosted runner to Kinsta over SSH, then runs `git fetch` and `git reset --hard` on Kinsta. [Kinsta: Git](https://kinsta.com/docs/wordpress-hosting/site-management/git/) [Kinsta: GitHub Actions integration](https://kinsta.com/docs/wordpress-hosting/site-management/wordpress-github-cicd/)

For this repository, the recommended end state is:

1. A push to `master` deploys the exact commit to the Kinsta **staging** environment.
2. The workflow purges Kinsta caches and runs a small staging smoke test.
3. A separate GitHub `production` environment, protected by manual approval where the GitHub plan supports it, deploys the same commit to the live environment.
4. The production job first creates and verifies a Kinsta manual backup, either in MyKinsta or through Kinsta's API.

This keeps the WordPress database, uploads, core, and Kinsta environment settings outside the Git deployment. A MyKinsta selective staging-to-live push is also viable and creates a target backup automatically, but even a files-only selective push overwrites destination environment settings such as redirects, geolocation, and PHP configuration. That makes a protected direct deployment of the already-tested Git commit preferable when staging and production settings differ. [Kinsta: push environments](https://kinsta.com/docs/wordpress-hosting/wordpress-push-environments/) [GitHub: deployments and environments](https://docs.github.com/en/actions/reference/workflows-and-actions/deployments-and-environments)

## Repository-specific findings

- The remote is `https://github.com/joelgrodriguez/amt-ntm.git`, and the deployment branch is `master`, not the `main` branch used in Kinsta's example.
- The repository root should live at `/www/<kinsta-site>/public/wp-content/themes/amt-ntm` on each Kinsta environment. Do **not** initialize this theme repository at `/www/<kinsta-site>/public`; a hard reset there would put the whole WordPress installation in the scope of this theme repository. Kinsta recommends running Git commands under `~/public` to avoid ownership problems. [Kinsta: Git preparation](https://kinsta.com/docs/wordpress-hosting/site-management/git/)
- WordPress activates the nested `app/` directory as the actual theme. The deploy must preserve the repository root plus `app/`; deploying only the contents of `app/` would change this repository's expected layout.
- Production uses hashed files from `app/dist/` and `app/dist/.vite/manifest.json`, but `.gitignore` excludes `app/dist/`. `npm run release:master` currently runs `npm run build` after its release commit and then pushes without force-adding the ignored output. Therefore, the official Kinsta server-side Git pull pattern cannot currently deliver the production CSS/JS build.
- Kinsta only documents npm availability on Single 1.9M+/WP 60+ plans, while Git and WP-CLI are available over SSH on all Managed WordPress plans. The release should not assume npm exists on the Kinsta server. [Kinsta: SSH](https://kinsta.com/docs/wordpress-hosting/connect-to-ssh/)

The asset issue must be resolved before enabling deployment. The simplest fit for Kinsta's documented Git-pull approach is to make `master` a deployable branch by committing `app/dist/` as part of the release process while continuing to ignore it on development branches if desired. The implemented alternative builds in GitHub Actions and transfers the resulting artifact over SFTP, Kinsta's supported file-transfer protocol. It replaces the complete nested `app/` theme so stale files do not survive, validates the incoming package before switching it into place, and retains one prior release for rollback. [Kinsta: SFTP](https://kinsta.com/docs/wordpress-hosting/connecting-with-sftp/)

## What Kinsta supports and limits

### SSH, SFTP, Git, and WP-CLI

All Managed WordPress plans include SSH, Git, and WP-CLI v2. In MyKinsta, each environment's **Info** page supplies its own host/IP, primary SFTP/SSH username, password, unique port, and WordPress path. The username is the SFTP username, not the account email. Staging and live credentials/ports must be treated separately. [Kinsta: SSH](https://kinsta.com/docs/wordpress-hosting/connect-to-ssh/) [Kinsta: WP-CLI](https://kinsta.com/docs/wordpress-hosting/site-management/wordpress-wp-cli/)

Kinsta permits only the primary SFTP/SSH user to use SSH. Additional SFTP users can be restricted to a folder and to read/write or read-only access, but they cannot execute SSH commands. SFTP is Kinsta's only supported file-transfer protocol. [Kinsta: SSH users](https://kinsta.com/docs/wordpress-hosting/connect-to-ssh/) [Kinsta: SFTP users](https://kinsta.com/docs/wordpress-hosting/connecting-with-sftp/)

Kinsta's Git client can pull a repository from GitHub, but MyKinsta is not a native Git deployment target. The official GitHub Actions guide automates the pull through an SSH command. [Kinsta: Git](https://kinsta.com/docs/wordpress-hosting/site-management/git/) [Kinsta: GitHub Actions integration](https://kinsta.com/docs/wordpress-hosting/site-management/wordpress-github-cicd/)

### Two separate SSH trust relationships

The server-side pull design has two independent SSH legs:

| Leg | Purpose | Private key location | Public key location |
| --- | --- | --- | --- |
| GitHub Actions -> Kinsta | Let the workflow execute the deploy command | GitHub `staging` or `production` environment secret | MyKinsta user SSH keys |
| Kinsta -> GitHub | Let Kinsta fetch the private repository | Kinsta environment's `~/.ssh/` | Repository **Settings > Deploy keys** |

Kinsta's official Actions example uses the primary SSH password for the first leg and stores host, username, password, and port as Actions secrets. Kinsta also supports inbound key authentication, which avoids a long-lived Kinsta password in Actions. The Kinsta-to-GitHub key should be a repository deploy key left read-only. GitHub deploy keys are repository-scoped and read-only by default, but they do not expire and their private half is normally unencrypted on the server; rotate/remove them when an environment is retired or compromised. [Kinsta: GitHub Actions setup](https://kinsta.com/docs/wordpress-hosting/site-management/wordpress-github-cicd/) [Kinsta: SSH keys](https://kinsta.com/docs/wordpress-hosting/connect-to-ssh/) [GitHub: deploy keys](https://docs.github.com/en/authentication/connecting-to-github-with-ssh/managing-deploy-keys)

Use separate GitHub secret sets for staging and production. Inbound public keys are added at the MyKinsta **user profile** level, not on an individual environment's Info page, so GitHub environment separation limits which workflow can read each private key but does not by itself prove that the key is authorized for only one Kinsta environment. Kinsta's documentation does not describe environment-scoped inbound SSH keys; do not assume that separation. The Kinsta-to-GitHub deploy keys, by contrast, can and should be generated separately inside each environment. Suggested GitHub environment configuration:

| GitHub environment | Secrets | Non-secret variables |
| --- | --- | --- |
| `staging` | `KINSTA_SSH_PRIVATE_KEY`, `KINSTA_KNOWN_HOSTS` | `KINSTA_HOST`, `KINSTA_PORT`, `KINSTA_USER`, `KINSTA_SITE_PATH` |
| `production` | separate `KINSTA_SSH_PRIVATE_KEY`, `KINSTA_KNOWN_HOSTS`, optionally `KINSTA_API_KEY` | separate host/port/user/path plus `KINSTA_ENVIRONMENT_ID` |

GitHub environment secrets are only exposed to jobs that reference that environment and, when required reviewers are configured, only after approval. Availability of required reviewers for private repositories depends on the GitHub plan. [GitHub: deployments and environments](https://docs.github.com/en/actions/reference/workflows-and-actions/deployments-and-environments) [GitHub: Actions secrets](https://docs.github.com/en/actions/how-tos/write-workflows/choose-what-workflows-do/use-secrets)

### Host verification and IP allowlists

Do not set `StrictHostKeyChecking=no`. Capture the host key for the exact `[IP]:port`, verify it through a trusted first connection/administrator, and store the resulting known-hosts entry as an environment secret. A non-standard Kinsta port means the known-hosts entry is port-qualified.

Kinsta warns that pushing or cloning environments regenerates an SSH host fingerprint. A later workflow can then fail with `REMOTE HOST IDENTIFICATION HAS CHANGED`. Re-verify the new fingerprint and replace the relevant `KINSTA_KNOWN_HOSTS` secret; locally, Kinsta documents removal with `ssh-keygen -R "[IP]:[Port]"` before reconnecting. Do not blindly accept a changed fingerprint because the same warning can indicate interception. [Kinsta: SSH/SFTP connection errors](https://kinsta.com/docs/wordpress-hosting/wordpress-troubleshooting/cant-connect-delete-ssh-known-hosts/) [Kinsta: push-environment fingerprint change](https://kinsta.com/docs/wordpress-hosting/wordpress-push-environments/)

Kinsta's official Actions guide says a MyKinsta SSH IP allowlist blocks GitHub-hosted runners because their addresses are dynamic and cannot be reliably allowlisted; its documented solution is to remove the allowlist. If an allowlist is mandatory, use a runner or gateway with stable outbound IP rather than a standard GitHub-hosted runner. The stable-egress design is an inference, not Kinsta's documented recipe. [Kinsta: GitHub Actions and IP allowlists](https://kinsta.com/docs/wordpress-hosting/site-management/wordpress-github-cicd/)

## Git-pull alternative and implemented artifact workflow

### Git-pull alternative: make `master` self-contained

1. Keep development work and build tooling on `dev` as the existing release process intends.
2. Run `npm ci`, PHP lint/tests, and `npm run build` before creating the release commit.
3. Verify `app/dist/.vite/manifest.json` exists and every manifest asset exists.
4. Include `app/dist/` in the `master` release commit. Do not rely on npm on Kinsta.
5. Push the completed release commit to `origin/master` only after those checks pass.

This alternative was not selected. The implemented artifact workflow leaves
`app/dist/` ignored and builds it on the GitHub runner.

### Phase 2: one-time Kinsta setup, separately for staging and live

1. In **MyKinsta > Sites > site > environment > Info**, record the environment's host, unique port, primary username, and `/www/.../public` path.
2. Add a dedicated inbound CI public key to MyKinsta and keep its private key in the matching GitHub environment secret.
3. SSH into the environment and place/initialize this repository at:

   ```text
   /www/<site>/public/wp-content/themes/amt-ntm
   ```

4. On that Kinsta environment, generate a separate outbound key. Add its public key to this GitHub repository as a **read-only deploy key**, and use the SSH remote `git@github.com:joelgrodriguez/amt-ntm.git`.
5. Confirm `ssh -T git@github.com`, `git fetch origin master`, and the exact theme/WP paths manually before automation.
6. Capture and verify the Kinsta inbound host key for GitHub Actions.

Kinsta's guide initializes the Git repository and then performs a hard reset. If the directory already contains the live theme, take a backup and inspect differences before the first reset. [Kinsta: official setup sequence](https://kinsta.com/docs/wordpress-hosting/site-management/wordpress-github-cicd/)

### Phase 3: deploy `master` to staging

Trigger on a push to `master`, use `environment: staging`, and serialize deployments with a dedicated concurrency group. Do not cancel a deployment once it has begun; replacing files mid-deploy can leave an uncertain state. GitHub concurrency groups ensure only one deployment in the group runs at a time. [GitHub: workflow concurrency](https://docs.github.com/en/actions/how-tos/write-workflows/choose-when-workflows-run/control-workflow-concurrency)

The remote script should fail on the first error and stay scoped to the theme:

```bash
set -euo pipefail

DEPLOY_SHA="${1:?pass the immutable commit SHA as argument 1}"
THEME_PATH="/www/<site>/public/wp-content/themes/amt-ntm"
WP_PATH="/www/<site>/public"

cd "$THEME_PATH"
git fetch --prune origin \
  +refs/heads/master:refs/remotes/origin/master
git merge-base --is-ancestor \
  "$DEPLOY_SHA" refs/remotes/origin/master
git reset --hard "$DEPLOY_SHA"
test "$(git rev-parse HEAD)" = "$DEPLOY_SHA"
test -f app/dist/.vite/manifest.json

cd "$WP_PATH"
wp kinsta cache purge --all
```

Pass the immutable `${{ github.sha }}` as `DEPLOY_SHA` and confirm that it is reachable from `origin/master`; deploying `origin/master` by name allows the branch to move between workflow checkout and remote execution. Avoid interpolating untrusted pull-request text into the remote shell.

Kinsta documents `wp kinsta cache purge --all` for site, edge, CDN, and Redis caches when the Kinsta MU plugin is installed. The purge endpoint must be accessible over HTTP, and manual purges are throttled to one request per ten seconds. If the theme has its own generated/cache layer, clear that before the Kinsta cache. [Kinsta: WP-CLI cache commands](https://kinsta.com/docs/wordpress-hosting/site-management/wordpress-wp-cli/) [Kinsta: server caching](https://kinsta.com/docs/wordpress-hosting/caching/site-caching/)

After deployment, test at least the staging home page, one representative template, and the built manifest/assets over HTTP. A green SSH step only proves commands completed, not that WordPress rendered successfully.

### Phase 4: promote the same commit to production

Preferred flow:

1. Require the staging job and smoke checks to pass.
2. Start a `production` job for the same `${{ github.sha }}`.
3. Gate it with a GitHub production environment approval where supported.
4. Create a tagged manual Kinsta backup and wait for completion before changing files. Kinsta exposes `POST /v2/sites/environments/{env_id}/manual-backups`; the initial `202` means the operation started, not that the backup finished, so poll the operation before deploying. [Kinsta API: add a manual backup](https://api-docs.kinsta.com/api-reference/backups/add-a-manual-backup-to-an-environment)
5. Run the same SHA-pinned theme-only deploy against the live environment.
6. Purge caches and smoke-test the public site.

Initially, create the production backup manually in MyKinsta rather than adding an API key to Actions. Add API automation only after scoping, storing, and rotating the key appropriately.

Alternative MyKinsta promotion:

- In staging, choose **Push Environment > Files > Specific files and folders** and select `wp-content/themes/amt-ntm`; do not select the database. Kinsta explicitly identifies a single theme folder as a selective-push use case and automatically backs up the target before the push. [Kinsta: selective push](https://kinsta.com/docs/wordpress-hosting/wordpress-push-environments/)
- Before using this path, compare staging and live redirects, geolocation, PHP, and other environment settings. Kinsta states those settings are included even in selective pushes and overwrite the destination. Kinsta also says custom Nginx configuration is retained, which is a narrower exception in the same document. Expect a couple of seconds of downtime at the final stage.
- Re-verify SSH fingerprints after the push.

## Safety boundaries and exclusions

The Git repository and every reset/cleanup command must remain inside `wp-content/themes/amt-ntm`. Never put any of these under this repository's control or deploy them from GitHub:

- `wp-config.php` or other secrets/environment files
- WordPress core (`wp-admin`, `wp-includes`, root PHP files)
- `wp-content/uploads`
- the live database
- unrelated themes, plugins, must-use plugins, caches, or logs
- local markers such as `app/.vite-dev-server`
- `node_modules`, local `.env*`, editor files, or development orchestration files

Kinsta's repository setup guidance likewise says to exclude WordPress core, media uploads, and sensitive information. [Kinsta: GitHub repository setup](https://kinsta.com/docs/wordpress-hosting/site-management/wordpress-github-cicd/)

Do not deploy the database for ordinary theme file changes. WordPress Customizer and dashboard settings normally live in the database and are a separate migration concern. On WooCommerce or other dynamic sites, pushing/rolling back a database can erase orders, form entries, sign-ups, comments, and content created after the source/backup point. [Kinsta: staging cautions](https://kinsta.com/docs/wordpress-hosting/staging-environment/) [Kinsta: push-environment cautions](https://kinsta.com/docs/wordpress-hosting/wordpress-push-environments/)

If a third-party action such as the `appleboy/ssh-action` shown in Kinsta's official example is used, pin it to a full commit SHA instead of a mutable tag and review what secrets it receives. GitHub identifies a full-length commit SHA as the only immutable action reference. A workflow using the runner's OpenSSH client directly has a smaller third-party-action trust surface. [GitHub: secure use of Actions](https://docs.github.com/en/actions/reference/security/secure-use)

## Rollback and atomicity

There are two rollback layers:

1. **Theme-only Git rollback:** approve a deployment of the previous known-good commit SHA. This is fast and does not alter the database or Kinsta settings.
2. **Kinsta snapshot restore:** restore the pre-deploy manual/system backup in MyKinsta when Git rollback is insufficient. Kinsta backups are complete environment snapshots including files, database, redirects, Nginx configuration, domains, and MyKinsta settings. Restoring therefore rolls back much more than this theme and can discard newer live data. Kinsta creates another backup immediately before a restore so the restore itself can be undone. [Kinsta: backups and restore scope](https://kinsta.com/docs/wordpress-hosting/wordpress-backups/)

Kinsta automatically creates a system backup before a MyKinsta staging-to-live push. Its documented list of automatic-backup events does not include an arbitrary SSH/Git deployment, so direct GitHub production deploys need an explicit backup step. [Kinsta: backup types](https://kinsta.com/docs/wordpress-hosting/wordpress-backups/)

Kinsta's official `git reset --hard` recipe updates the working tree in place; the documentation does not claim it is atomic. It also does not remove untracked/stale files. Do not add `git clean -fdx` casually: it can delete runtime or manually placed files, and `-x` would delete ignored build assets under the repository's current rules. A release-directory/symlink design could provide a more atomic cutover, but it would require a deliberate one-time restructure and Kinsta validation; it is not part of Kinsta's documented WordPress Git recipe.

## Go/no-go checklist

Do not enable automatic deployment until every answer is yes:

- [x] The deploy artifact contains `app/dist/.vite/manifest.json` and its built assets.
- [ ] Staging and live use distinct GitHub environments and credentials.
- [ ] Both remote repository roots are exactly under `wp-content/themes/amt-ntm`.
- [ ] Kinsta-to-GitHub deploy keys are read-only and environment-specific.
- [ ] GitHub-to-Kinsta host keys are verified; host checking remains enabled.
- [ ] The SSH IP-allowlist decision is explicit.
- [ ] Deployments are serialized and deploy an immutable SHA.
- [ ] Staging rendering and asset smoke tests pass.
- [ ] Production requires approval where supported.
- [ ] A production backup completes before direct deployment.
- [ ] A previous known-good SHA and Kinsta restore procedure are recorded.
- [ ] No database, uploads, core, secrets, or unrelated site files are in deploy scope.

## Primary sources

- [Kinsta: Git](https://kinsta.com/docs/wordpress-hosting/site-management/git/)
- [Kinsta: GitHub Actions integration](https://kinsta.com/docs/wordpress-hosting/site-management/wordpress-github-cicd/)
- [Kinsta: SSH](https://kinsta.com/docs/wordpress-hosting/connect-to-ssh/)
- [Kinsta: SFTP](https://kinsta.com/docs/wordpress-hosting/connecting-with-sftp/)
- [Kinsta: SSH and SFTP connection errors](https://kinsta.com/docs/wordpress-hosting/wordpress-troubleshooting/cant-connect-delete-ssh-known-hosts/)
- [Kinsta: staging environments](https://kinsta.com/docs/wordpress-hosting/staging-environment/)
- [Kinsta: push environments](https://kinsta.com/docs/wordpress-hosting/wordpress-push-environments/)
- [Kinsta: WordPress backups](https://kinsta.com/docs/wordpress-hosting/wordpress-backups/)
- [Kinsta: WP-CLI](https://kinsta.com/docs/wordpress-hosting/site-management/wordpress-wp-cli/)
- [Kinsta: server caching](https://kinsta.com/docs/wordpress-hosting/caching/site-caching/)
- [Kinsta API: add a manual backup](https://api-docs.kinsta.com/api-reference/backups/add-a-manual-backup-to-an-environment)
- [GitHub: managing deploy keys](https://docs.github.com/en/authentication/connecting-to-github-with-ssh/managing-deploy-keys)
- [GitHub: deployments and environments](https://docs.github.com/en/actions/reference/workflows-and-actions/deployments-and-environments)
- [GitHub: using Actions secrets](https://docs.github.com/en/actions/how-tos/write-workflows/choose-what-workflows-do/use-secrets)
- [GitHub: workflow concurrency](https://docs.github.com/en/actions/how-tos/write-workflows/choose-when-workflows-run/control-workflow-concurrency)
- [GitHub: secure use reference](https://docs.github.com/en/actions/reference/security/secure-use)
