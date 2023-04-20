import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { CredentialService } from '../credential.service';

@Injectable({
  providedIn: 'root'
})
export class EvaluateService {
  readonly #clientHeaders: { [header: string]: string };
  constructor(private http: HttpClient, private credentials: CredentialService) {
    this.#clientHeaders = {
      Authorization: `Bearer ${this.credentials.token}`,
      Accept: 'application/json',
      'Content-Type': 'application/json',
    };
  }
}
