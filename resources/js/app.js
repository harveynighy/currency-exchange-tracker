import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('[data-mobile-nav-toggle]').forEach((toggle) => {
		const menuId = toggle.getAttribute('aria-controls');
		const menu = menuId ? document.getElementById(menuId) : null;

		if (!menu) {
			return;
		}

		const closeMenu = () => {
			menu.classList.remove('is-open');
			menu.setAttribute('aria-hidden', 'true');
			toggle.setAttribute('aria-expanded', 'false');
		};

		toggle.addEventListener('click', () => {
			const isOpen = menu.classList.toggle('is-open');

			menu.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
			toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
		});

		menu.querySelectorAll('a, button').forEach((element) => {
			element.addEventListener('click', () => {
				if (window.innerWidth < 820) {
					closeMenu();
				}
			});
		});

		window.addEventListener('resize', () => {
			if (window.innerWidth >= 820) {
				closeMenu();
			}
		});
	});
});
