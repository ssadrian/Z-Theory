import {HttpClient} from '@angular/common/http';
import {Injectable} from '@angular/core';
import {Router} from '@angular/router';
import {CredentialService} from './credential.service';

@Injectable({
  providedIn: 'root',
})
export class LoginService {
  constructor(private _http: HttpClient, private router: Router, private credentials: CredentialService) {
  }

  login(creds: { email: string, password: string }): void {
    this.credentials.email = creds.email;
    this.credentials.password = creds.password;

    this.router.navigate(['/profile']);
  }

  logout(): void {
    this.credentials.clear();
    this.router.navigate(['/']);
  }
}
