import Login from '../login/login.js'

export default class Router{
    constructor(params){
      let self = this;
      self.rootElement = {};
      self.prefixPathname = '/';
      self.views = {};
      self.loginController = false;
      self.errorView = document.createElement("div");
      self.errorView.innerHTML = `
          <h1>404 not found</h1>
      `;

      if(params.hasOwnProperty('rootElement')){
          self.rootElement = params.rootElement;
      }
      if(params.hasOwnProperty('prefixPathname')){
        self.prefixPathname = params.prefixPathname;
      }
      if(params.hasOwnProperty('loginController')){
        if(params.loginController instanceof Login){
          self.loginController = params.loginController;
        }
      }

    }

    addView(viewUrl, viewElement, viewRenderStatus = [()=>{return true;}, ""]) {
      let self = this;
      
      if(!viewUrl || !viewElement){
        console.error(`You should provide all 2 parameters: a pathname and an element`);
        return false;
      }
      if(viewElement.nodeType !== Node.ELEMENT_NODE){
        console.error(`Inserted view is not a node`);
        return false;
      }
      
      self.views[self.prefixPathname+viewUrl] = [viewElement, 
        [
          viewRenderStatus[0],
          self.prefixPathname+viewRenderStatus[1]
        ]
      ];
      return true;
    }

    addErrorView(element){
      let self = this;
      if(element.nodeType !== Node.ELEMENT_NODE){
        console.error(`Error inserted view is not a node`);
        return false;
      }
      self.errorView = element;
    }

    checkPathnameExists(pathname) {
      let self = this;
      return (self.views[pathname])
        ? pathname
        : false;
    }

    constructView (pathname) {
      let self = this;
      let verifiedPathname = self.checkPathnameExists(pathname);
      let result = {}
      if (verifiedPathname){
        if(!self.views[verifiedPathname][1][0]()){
          verifiedPathname = self.views[verifiedPathname][1][1];
        }
        result["path"] = verifiedPathname;
        result["element"] = self.views[verifiedPathname][0];
      }else{
        result["path"] = pathname;
        result["element"] = self.errorView;
      }
      return result;
    }

    setPathname(pathname) {
      window.history.pushState({}, pathname, window.location.origin + pathname);
    }

    replaceAnchorClick() {
      let self = this;
      let customNavigators = document.querySelectorAll(".customAnchor");
      customNavigators.forEach((navigator) => {
        navigator.onclick = (evt) =>{
          evt.preventDefault();
          self.navigateTo(navigator.getAttribute("href"));
        };
      });
    }

    addOtherHandlers = () => {
      //move this to every component later on
      let self = this;
      let loginForm = document.querySelector("#loginForm");
      if(loginForm){
        loginForm.onsubmit = (evt) => {
          evt.preventDefault();
          let username = loginForm.querySelector("#userName").value;
          let password = loginForm.querySelector("#password").value;
          let loginData = `{
            "email": "${username}",
            "password": "${password}"
          }`;
          self.loginController.setLoginData(loginData);
          self.loginController.login();
        }
      }
      let logoutButton = document.querySelector('#logoutButton');
      if(logoutButton){
        logoutButton.onclick = () => {
          self.loginController.logout();
          self.navigateTo("/login");
        }
      }
    }

    setCurrentViewContent(element) {
      let self = this;
      self.rootElement.innerHTML = "";
      self.rootElement.appendChild(element);
      self.replaceAnchorClick();
      self.addOtherHandlers();
    }

    navigateTo(pathname) {
      let self = this;
      let newView = self.constructView(self.prefixPathname+pathname);
      self.setPathname(newView.path);
      self.setCurrentViewContent(newView.element);
    }

    checkPathnameFromWindowLocation(initialPathname) {
      let self = this;
      let cleanPathname = initialPathname.replace(self.prefixPathname, "");
      return (cleanPathname.substr(initialPathname.length - 1) === "/")
        ? cleanPathname.slice(0, -1): cleanPathname;
    }

    setPopStateEvent() {
      let self = this;
      window.onpopstate = () => {
        let previousPathname = self.checkPathnameFromWindowLocation(
          window.location.pathname);
        let previousView = self.constructView(self.prefixPathname+previousPathname);
        window.history.replaceState({}, null, previousView.path);
        self.setCurrentViewContent(previousView.element);
      }
    }

    start() {
      let self = this;
      let initialPathname = self.checkPathnameFromWindowLocation(
        window.location.pathname);
      let initialView = self.constructView(
        self.prefixPathname+initialPathname);
      window.history.replaceState({}, null, initialView.path);
      self.setCurrentViewContent(initialView.element);
      self.setPopStateEvent();
    }

}