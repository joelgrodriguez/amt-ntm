import { init } from '../../app/resources/js/modules/ScrollHeader.js';

class ClassList {
  constructor(...classes) {
    this.classes = new Set(classes);
  }

  add(...classes) {
    classes.forEach((className) => this.classes.add(className));
  }

  remove(...classes) {
    classes.forEach((className) => this.classes.delete(className));
  }

  contains(className) {
    return this.classes.has(className);
  }
}

function createElementStub(...classes) {
  return {
    classList: new ClassList(...classes),
    addEventListener() {},
    removeEventListener() {},
    contains() { return false; },
  };
}

function assert(condition, message) {
  if (!condition) {
    throw new Error(message);
  }
}

const header = createElementStub();
const machineSubnav = createElementStub('is-sticky');
const elements = new Map([
  ['site-header', header],
  ['machine-subnav', machineSubnav],
]);

globalThis.document = {
  activeElement: null,
  body: createElementStub(),
  getElementById(id) { return elements.get(id) ?? null; },
  querySelector() { return null; },
};

globalThis.window = {
  scrollY: 0,
  addEventListener(type, callback) {
    if (type === 'scroll') this.onScroll = callback;
  },
  removeEventListener() {},
  setTimeout() { return 1; },
  clearTimeout() {},
};

let animationFrame = null;
globalThis.requestAnimationFrame = (callback) => {
  animationFrame = callback;
};

function flushAnimationFrame() {
  const callback = animationFrame;
  animationFrame = null;
  callback();
}

const cleanup = init();

window.scrollY = 200;
window.onScroll();
flushAnimationFrame();
assert(header.classList.contains('header--hidden'), 'Scrolling down should hide the header.');

window.scrollY = 150;
window.onScroll();
flushAnimationFrame();
assert(header.classList.contains('header--sticky'), 'Scrolling up should make the header sticky.');
assert(!header.classList.contains('header--hidden'), 'A sticky machine subnav must not suppress the global header reveal.');
assert(document.body.classList.contains('header-is-revealed'), 'The page should expose the revealed-header state so fixed subnavs can move below it.');

window.scrollY = 200;
window.onScroll();
flushAnimationFrame();
assert(!document.body.classList.contains('header-is-revealed'), 'Scrolling down should clear the revealed-header coordination state.');

cleanup();
console.log('Scroll header tests passed.');
