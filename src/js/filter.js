const filterInput = document.getElementById("filterInput");

document.addEventListener("DOMContentLoaded", function(event) {
  registerEventListeners();
});

function registerEventListeners() {
  filterInput.addEventListener("keyup", filterVenues);
}

function filterVenues(e) {
  const text = e.target.value.toLowerCase();
  const table = document.getElementById("myTable");
  const tr = table.getElementsByTagName("tr");

  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toLowerCase().indexOf(text) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
