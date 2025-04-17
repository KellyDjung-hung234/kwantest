
    //顯示密碼
    function showPassword() {
        var passwordInput = document.getElementById("Password");
        var confirmInput = document.getElementById("confirmPassword");
        var showButton = document.getElementById("showButton");
    
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            confirmInput.type = "text";
            showButton.value = "hide";
        } else {
            passwordInput.type = "password";
            confirmInput.type = "password";
            showButton.value = "show";
        }
    }
    
    //檢查密碼是否相同 
    function checkPassword() {
        var password = document.getElementById("Password").value;
        var confirmPassword = document.getElementById("confirmPassword").value;
        if (password != confirmPassword) {
            alert("Password and the Password Confirm do not match.");
            return false;
        }
        return true;
    }

    //檢查密碼是否符合規則
    function checkVaildPassword() {
        var password = document.getElementById("Password").value;
        var pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,16}$/;
        if (!pattern.test(password)) {
            alert("Password must contain at least 8 characters and not more than 16 characters, including at least one uppercase letter, one lowercase letter, and one number.");
            return false;
        }
        return true;
        }


