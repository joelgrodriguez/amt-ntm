/**
 * Mega Menu Module
 *
 * Handles click-to-open mega menu dropdowns for desktop navigation.
 *
 * @file MegaMenu.js
 */

/**
 * Initializes the mega menu functionality.
 *
 * @returns {Function} Cleanup function to remove event listeners
 */
export const initMegaMenu = () => {
  const menuItems = document.querySelectorAll('#primary-menu > li.menu-item-has-children');
  const overlay = document.getElementById('mega-menu-overlay');

  if (!menuItems.length) {
    return () => {};
  }

  /**
   * Closes all open menus and hides overlay.
   */
  const closeAllMenus = () => {
    menuItems.forEach((item) => {
      item.classList.remove('menu-open');
    });
    overlay?.classList.remove('active');
    document.body.classList.remove('overflow-hidden');
  };

  /**
   * Shows the overlay and locks scroll.
   */
  const showOverlay = () => {
    overlay?.classList.add('active');
    document.body.classList.add('overflow-hidden');
  };

  /**
   * Positions the submenu to align with the container edge.
   *
   * @param {HTMLElement} menuItem - The menu item with submenu
   */
  const positionSubmenu = (menuItem) => {
    const submenu = menuItem.querySelector('.sub-menu');
    if (!submenu) return;

    const container = document.querySelector('.lg\\:container');
    if (!container) return;

    const containerRect = container.getBoundingClientRect();
    const offset = containerRect.left;

    submenu.style.setProperty('--submenu-offset', `${menuItem.getBoundingClientRect().left - offset}px`);
  };

  /**
   * Handles click on menu items with children.
   *
   * @param {Event} event - Click event
   */
  const handleMenuClick = (event) => {
    const menuItem = event.currentTarget.closest('li.menu-item-has-children');

    if (!menuItem) return;

    event.preventDefault();

    const isOpen = menuItem.classList.contains('menu-open');

    closeAllMenus();

    if (!isOpen) {
      positionSubmenu(menuItem);
      menuItem.classList.add('menu-open');
      showOverlay();
    }
  };

  /**
   * Handles clicks outside the menu to close it.
   *
   * @param {Event} event - Click event
   */
  const handleOutsideClick = (event) => {
    const isInsideMenu = event.target.closest('#primary-menu');
    if (!isInsideMenu) {
      closeAllMenus();
    }
  };

  /**
   * Handles escape key to close menus.
   *
   * @param {KeyboardEvent} event - Keyboard event
   */
  const handleEscapeKey = (event) => {
    if (event.key === 'Escape') {
      closeAllMenus();
    }
  };

  // Attach event listeners
  menuItems.forEach((item) => {
    const link = item.querySelector(':scope > a');
    if (link) {
      link.addEventListener('click', handleMenuClick);
    }
  });

  overlay?.addEventListener('click', closeAllMenus);
  document.addEventListener('keydown', handleEscapeKey);

  // Cleanup function
  return () => {
    menuItems.forEach((item) => {
      const link = item.querySelector(':scope > a');
      if (link) {
        link.removeEventListener('click', handleMenuClick);
      }
    });
    overlay?.removeEventListener('click', closeAllMenus);
    document.removeEventListener('keydown', handleEscapeKey);
  };
};
