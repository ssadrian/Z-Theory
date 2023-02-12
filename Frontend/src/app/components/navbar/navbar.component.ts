import {Component} from "@angular/core";
import {LoginService} from "../../services/login.service";
import {CredentialService} from "../../services/credential.service";

@Component({
  selector: "app-navbar",
  templateUrl: "./navbar.component.html",
  styleUrls: ["./navbar.component.scss"],
})
export class NavbarComponent {
  constructor(private loginService: LoginService, private credentials: CredentialService) {
  }

  get isLogged(): boolean {
    return this.credentials.currentUser !== undefined;
  }

  logOut(): void {
    this.loginService.logout();
  }
}
