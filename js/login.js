$(document).ready(function () {
  $("#loginForm").submit(function (e) {
    e.preventDefault();

    $.ajax({
      type: "POST",
      url: "api/login.php",
      data: {
        action: "login",
        username: $("#username").val(),
        password: $("#password").val(),
      },
      dataType: "json",
      success: function (response) {
        if (response.status === "success") {
          alert(response.message);

          if (response.user_type === "admin") {
            window.location.href = "adminhome.php";
          } else if (response.user_type === "employee") {
            window.location.href = "employeehome.php";
          } else {
            window.location.href = "index.php";
          }
        } else if (
          response.status === "user_not_found" ||
          response.status === "error"
        ) {
          alert(response.message);
        } else if (response.status === "debug") {
          alert(response.message);
        }
      },
      error: function (xhr, status, error) {
        alert("Error during login. Status: " + status + ", Error: " + error);
        console.log(xhr.responseText);
      },
    });
  });
});
