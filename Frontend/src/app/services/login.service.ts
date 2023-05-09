import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { CredentialService } from './credential.service';
import { environment } from '../environments/environment';
import { HttpClient } from '@angular/common/http';
import { CookieService } from 'ngx-cookie-service';

@Injectable({
  providedIn: 'root',
})
export class LoginService {
  constructor(
    private router: Router,
    private credentials: CredentialService,
    private http: HttpClient,
    private cookieService: CookieService
  ) {}

  #apiUrl: string = `${environment.apiUrl}`;
  #sessionCookie: string = environment.sessionCookieName;

  login(
    creds: { email: string; password: string },
    rememberMe: boolean = false
  ): void {
    this.credentials.email = creds.email;
    this.credentials.password = creds.password;

    if (rememberMe) {
      this.cookieService.set('rememberMe', 'true');
    }

    this.router.navigate(['/profile']);
  }

  logout(): void {
    const url: string = `${this.#apiUrl}/logout`;
    this.http.get(url).subscribe();

    this.cookieService.delete(this.#sessionCookie, '/');
    this.cookieService.delete('rememberMe', '/');

    this.credentials.clear();
    this.router.navigate(['/']);
  }
}
