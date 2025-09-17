import './bootstrap';

window.sidebar = sidebar;
function sidebar(){
    const sidebar = document.getElementById("sidebar");
    sidebar.classList.toggle("-translate-x-full");
    // sidebar.classList.toggle("opacity-0");
    sidebar.classList.toggle("pointer-events-none");
    console.log("Sidebar toggled");
}

window.togglePasswordVisibility = togglePasswordVisibility;
function togglePasswordVisibility(){
    var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}

window.showEmployeeModal = showEmployeeModal;
function showEmployeeModal(x){
    if(x==1){
        var modalForm = document.getElementById("employeeModalForm");
        modalForm.reset();
    } else if(x==2){
        // Show modal for editing employee
    }
    var modalBg = document.getElementById("modalBg");
    var modal = document.getElementById("employeeModal");
    modal.classList.toggle("hidden");
    modalBg.classList.toggle("hidden");
}

// this one enables us to select rows
 document.addEventListener("DOMContentLoaded", function () {
    const table = document.getElementById("employeeTable");

    for (let i = 1; i < table.rows.length; i++) {
      let row = table.rows[i];

      row.addEventListener("click", function () {
        const cells = this.cells;
        //id's with m sa first is tung sa modal when naka phone size huhuhu
        // Map table data to form fields
        document.getElementById("id").value = cells[0].innerText;
        document.getElementById("firstname").value = cells[1].innerText;
        document.getElementById("lastname").value = cells[2].innerText;
        document.getElementById("contactnumber").value = cells[3].innerText;

        document.getElementById("mid").value = cells[0].innerText;
        document.getElementById("mfirstname").value = cells[1].innerText;
        document.getElementById("mlastname").value = cells[2].innerText;
        document.getElementById("mcontactnumber").value = cells[3].innerText;

        //Add values through database (idk pud how huhu)
        document.getElementById("username").value = "null";
        document.getElementById("password").value = "null";
        document.getElementById("status").value = "inactive";
        document.getElementById("role").value = "Admin";

        document.getElementById("musername").value = "null";
        document.getElementById("mpassword").value = "null";
        document.getElementById("mstatus").value = "inactive";
        document.getElementById("mrole").value = "Admin";
        //Add values through database (idk pud how huhu)

        // Remove highlight from all rows first
        for (let j = 0; j < table.rows.length; j++) {
          table.rows[j].classList.remove("bg-lime-200");
        }
        // Highlight the selected row
        row.classList.add("bg-lime-200");
      });
    }
  });

  window.clearhighlight = clearhighlight;
  function clearhighlight(){
    const table = document.getElementById("employeeTable");
    for (let j = 0; j < table.rows.length; j++) {
      table.rows[j].classList.remove("bg-lime-200");
    }
  }

  window.toggleEditProfile = toggleEditProfile;
  function toggleEditProfile() {
    document.getElementById("editBtn").classList.toggle("hidden");
    document.getElementById("cancelBtn").classList.toggle("hidden");
    document.getElementById("saveBtn").classList.toggle("hidden");

    document.getElementById("first_name").readOnly = !document.getElementById("first_name").readOnly;
    document.getElementById("last_name").readOnly = !document.getElementById("last_name").readOnly;
    document.getElementById("contact_number").readOnly = !document.getElementById("contact_number").readOnly;
    document.getElementById("username").readOnly = !document.getElementById("username").readOnly;
    document.getElementById("password").readOnly = !document.getElementById("password").readOnly;
  }