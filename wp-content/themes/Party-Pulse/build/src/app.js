document.addEventListener("DOMContentLoaded", function() {
  var menuItems = document.querySelectorAll(".site-header .main-navigation .primary-menu li");
  var icons = [
    "/wp-content/uploads/burger.png",
    "/wp-content/uploads/party.png",
    "/wp-content/uploads/Spaceship.png"
  ];
  menuItems.forEach(function(item, index) {
    var iconIndex = index % icons.length;
    var iconUrl = icons[iconIndex];
    var iconElement = document.createElement("img");
    iconElement.src = iconUrl;
    iconElement.alt = "Icon";
    iconElement.classList.add("menu-icon", "icon-" + (iconIndex + 1));
    item.appendChild(iconElement);
  });
});
