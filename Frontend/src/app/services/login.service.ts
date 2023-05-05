import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { CredentialService } from './credential.service';
import { environment } from '../environments/environment';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root',
})
export class LoginService {
  constructor(
    private router: Router,
    private credentials: CredentialService,
    private http: HttpClient
  ) {}

  #apiUrl: string = `${environment.apiUrl}`;

  login(creds: { email: string; password: string }): void {
    this.credentials.email = creds.email;
    this.credentials.password = creds.password;

    this.router.navigate(['/profile']);
  }

  logout(): void {
    const url: string = `${this.#apiUrl}/logout`;
    this.http
      .get(url, {
        headers: {
          Authorization: `Bearer ${this.credentials.token}`,
        },
      })
      .subscribe();

    this.credentials.clear();
    this.router.navigate(['/']);
  }
}
