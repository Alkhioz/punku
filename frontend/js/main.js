import Router from './router/router.js';
import Login from './login/login.js'

const prefixPathname = "/punku";
const loginUrl = "https://reqres.in/api/login";

const loginCallback = (data) => {
  
  if(data.internalStatus){
    loginController.addToken(data.token);
    routerController.navigateTo("/main");
  }

}

let rootElement = document.querySelector("#rootElement");

let loginController = new Login({
  loginUrl,
  loginCallback
});

let routerController = new Router({
  rootElement,
  prefixPathname,
  loginController
});

const homeView = document.createElement("div");
homeView.innerHTML = `
  <div class="header-img"></div>
  <div class="center">
      <h1 class="big">Punku issue tracker</h1>
      <div class="maxw500">
          <p class="normal justify">Welcome this is a simple yet powerfull web based issue tracker that make collaboration with team members
          fast and professional</p>
      </div>
      <br>
      <a href="/login" class="customAnchor white bkprimary undecorated normal pad10 highlight">Let's Start!</a>
  </div>
`;

const loginView = document.createElement("div");
loginView.innerHTML = `
  <div class="center"> 
    <div class="maxw500 pad10">
      <h1 class="big">Punku Issue Tracker | Login</h1>
      <form id="loginForm">
        <label for="userName" class="black block normal txtjustify w100 mart10">User Name</label>
        <input autocomplete="username" type="text" class="block w100 mart10 pad10" id="userName" name="userName" required>
        <label for="password" class="black block normal txtjustify mart10">Password</label>
        <input autocomplete="current-password" type="password" class="block w100 mart10 pad10" id="password" name="password" required>
        <input type="submit" value="login" class="bkprimary w100 white normal mart10 undecorated pad10 highlight" >
      </form>
      <div class="w100 txtjustify mart10">
        <a class="customAnchor primary undecorated highlightText" href="/">&#8592; Go back</a>
      </div>
    </div>
  </div>
`;

const mainView = document.createElement("div");
mainView.innerHTML = `
  <div class="center"> 
    <div class="maxw500 pad10">
      <h1 class="big">This is the main view</h1>
      <input type="button" value="logout" id="logoutButton">
    </div>
  </div>
`;


let errorView = document.createElement("div");
errorView.innerHTML = `
  <h1>Custom error View</h1>
  <a class="customAnchor" href="/">Go to main</a>
`;

routerController.addView("/", homeView);
routerController.addView("/login", loginView, [()=> !loginController.isLoggedIn() , "/main"]);
routerController.addView("/main", mainView, [loginController.isLoggedIn, "/login"]);
routerController.addErrorView(errorView);
routerController.start();

//loginController.login();
//loginController.logout();
//loginController.isLoggedIn();
//console.log(routerController.checkLogin());