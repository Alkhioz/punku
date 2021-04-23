export default class Login{
    constructor(params){
        self = this;
        self.loginUrl = "";
        self.loginCallback = {};
        self.loginData = "";

        if(params.hasOwnProperty('loginUrl')){
            self.loginUrl = params.loginUrl;
        }

        if(params.hasOwnProperty('loginCallback')){
            self.loginCallback = params.loginCallback;
        }

        if(params.hasOwnProperty('loginData')){
            self.loginData = params.loginData;
        }
    }

    setLoginData(loginData) {
        let self = this;
        self.loginData = loginData;
    }

    addToken(token){
        if(localStorage.getItem("token") === null){
            localStorage.setItem('token', token);
            return true;
        }
        console.error(`Token is already set in localStorage`);
        return false;
    }

    removeToken(){
        if(localStorage.getItem("token") === null){
            console.error(`There is no token to remove`);
            return false;
          }
        localStorage.removeItem('token');
        return true;
    }

    isLoggedIn() {
        return (localStorage.getItem('token'))
            ? true : false;
    }

    login(){
        let self = this;
        let xhr = new XMLHttpRequest();
        let method = "POST";
        xhr.onreadystatechange = function () {
            if(xhr.readyState === XMLHttpRequest.DONE) {
                let status = xhr.status;
                if (status === 0 || (status >= 200 && status < 400)) {
                    let response = JSON.parse(xhr.responseText);
                    response.internalStatus = "true";
                    self.loginCallback(response);
                    return true;
                }
                let response = JSON.parse(`{"internalStatus": false}`);
                self.loginCallback(response);
                return false;
            }
        }
        xhr.open(method, self.loginUrl, true);
        xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        xhr.send(self.loginData);
    }

    logout(){
        self = this;
        self.removeToken();
    }

}