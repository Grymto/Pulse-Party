

document.addEventListener('DOMContentLoaded', function() {
    var menuItems = document.querySelectorAll('.site-header .main-navigation .primary-menu li');
    var icons = [
        '/wp-content/uploads/burger.png',
        '/wp-content/uploads/party.png',
        '/wp-content/uploads/Spaceship.png'
    ]; // Add the paths to your icons here

    menuItems.forEach(function(item, index) {
        var iconIndex = index % icons.length;
        var iconUrl = icons[iconIndex];
        var iconElement = document.createElement('img');
        iconElement.src = iconUrl;
        iconElement.alt = 'Icon';
        iconElement.classList.add('menu-icon', 'icon-' + (iconIndex + 1)); // Add classes icon-1, icon-2, icon-3
        item.appendChild(iconElement); // Append the icon as a child of the list item
    });
});

