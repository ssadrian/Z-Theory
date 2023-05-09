import { Component, OnInit } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';
import { PrimeNGConfig } from 'primeng/api';
import { environment } from './environments/environment';
import { ISessionCookie } from 'src/models/session-cookie.model';
import { LoginService } from './services/login.service';
import { CredentialService } from './services/credential.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss'],
})
export class AppComponent implements OnInit {
  constructor(
    private primengConfig: PrimeNGConfig,
    private loginService: LoginService,
    private credentialService: CredentialService,
    private cookieService: CookieService
  ) {}

  title = 'Frontend';
  #sessionCookie: string = environment.sessionCookieName;

  ngOnInit(): void {
    this.primengConfig.ripple = true;

    const cookie: string = this.cookieService.get(this.#sessionCookie);
    if (cookie) {
      const credentials: ISessionCookie = JSON.parse(cookie);
      this.credentialService.set(credentials);
    }
  }
}
