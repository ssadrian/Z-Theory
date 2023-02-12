import {HttpClient} from '@angular/common/http';
import { Injectable } from '@angular/core';
import { CanMatch, Route, UrlSegment, UrlTree } from '@angular/router';
import { Observable } from 'rxjs';
import {environment} from '../environments/environment';
import {CredentialService} from '../services/credential.service';

@Injectable({
  providedIn: 'root'
})
export class StudentGuard implements CanMatch {
  constructor(
    private _http: HttpClient,
    private credentials: CredentialService) {
  }

  #loginUrl: string = `${environment.apiUrl}/login/student`;

  canMatch(
    route: Route,
    segments: UrlSegment[]): Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {
    this._http.post(this.#loginUrl, JSON.stringify({
      'email': this.credentials.email,
      'password': this.credentials.password,
    })).subscribe((response): void => {
      // TODO: Set token, do same for teacher
    });
  }
}
