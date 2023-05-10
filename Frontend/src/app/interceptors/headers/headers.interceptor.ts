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
export class HeadersInterceptor implements HttpInterceptor {
  constructor(private credentialService: CredentialService) {}

  intercept(
    request: HttpRequest<unknown>,
    next: HttpHandler
  ): Observable<HttpEvent<unknown>> {
    const token: string = this.credentialService.token;

    console.log(`Sending request to: ${request.url} with token: ${token}`);
    const req = request.clone({
      setHeaders: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        Authorization: `Bearer ${token}`,
      },
    });

    return next.handle(req);
  }
}
