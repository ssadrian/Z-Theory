import { Injectable } from '@angular/core';
import {
  HttpRequest,
  HttpHandler,
  HttpEvent,
  HttpInterceptor,
} from '@angular/common/http';
import { Observable } from 'rxjs';
import { CredentialService } from '../../services/credential.service';

@Injectable()
export class AuthInterceptor implements HttpInterceptor {
  constructor(private credentialService: CredentialService) {}

  intercept(
    request: HttpRequest<unknown>,
    next: HttpHandler
  ): Observable<HttpEvent<unknown>> {
    const token: string = this.credentialService.token;

    if (token.trim().length > 0) {
      const authReq = request.clone({
        setHeaders: {
          Authorization: `Bearer ${token}`,
        },
      });

      return next.handle(authReq);
    }

    return next.handle(request);
  }
}
