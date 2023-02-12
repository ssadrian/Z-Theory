import { Injectable } from '@angular/core';
import { CanMatch, Route, UrlSegment, UrlTree } from '@angular/router';
import { Observable } from 'rxjs';
import {CredentialService} from '../services/credential.service';

@Injectable({
  providedIn: 'root'
})
export class TeacherGuard implements CanMatch {
  constructor(private credentials: CredentialService) {
  }

  canMatch(
    route: Route,
    segments: UrlSegment[]): Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {
    return this.credentials.isLoggedIn;
  }
}
