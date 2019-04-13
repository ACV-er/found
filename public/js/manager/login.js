let login_form;
let result;
let error;
window.onload = function() {
    login_form = document.getElementById("form");
    error = document.getElementById('error');
};

function login() {
    stu_id = document.getElementById('stu_id').value;
    password = document.getElementById('password').value;
    console.log(stu_id);

    if(!/(^20[\d]{8,10}$)|(^[a-z]{2,10}$)/.test(stu_id) || !/^[^\s]{8,20}$/.test(password) ) {
        alert("账号密码格式有误！");
        return;
    }
    let data = new FormData(login_form);
    let ajax = new XMLHttpRequest();
    console.log(login_form);
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 4 && ajax.status == 200) {
            result = JSON.parse(ajax.responseText);
            if(result.code == 2) {
                error.innerHTML = "账号密码错误";
            } else if(result.code != 0) {
                error.innerHTML = "未知错误，联系技术部";
            } else {
                window.location.href = "https://found.sky31.com/manager/post";
            }
        }
    };

    ajax.open("POST", "https://found.sky31.com/manager/login", true);

    ajax.send(data);
}
