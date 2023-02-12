import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class CredentialService {
  email: string = '';
  password: string = '';
  token: string = '';

  get isLoggedIn(): boolean {
    return this.token.length > 0;
  }

  clear(): void {
    this.email = '';
    this.password = '';
    this.token = '';
  }
}
