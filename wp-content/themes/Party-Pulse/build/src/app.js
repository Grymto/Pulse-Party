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
  });
});
