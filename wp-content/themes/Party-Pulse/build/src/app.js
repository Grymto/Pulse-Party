document.addEventListener("DOMContentLoaded", function() {

  var filterBtn = document.getElementById("filterBtn");
  var filterCover = document.getElementById("filterCover");
  var closeFilterBtn = document.getElementById("closeFilterBtn");
  filterBtn.addEventListener("click", function() {
    filterCover.style.display = "block";
  });
  filterCover.addEventListener("click", function(e) {
    if (e.target === filterCover) {
      filterCover.style.display = "none";
    }
  });
  closeFilterBtn.addEventListener("click", function() {
    filterCover.style.display = "none";
  })
})

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

