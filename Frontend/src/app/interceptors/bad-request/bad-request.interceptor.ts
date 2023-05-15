import { Injectable } from '@angular/core';
import {
  HttpRequest,
  HttpHandler,
  HttpEvent,
  HttpInterceptor,
  HttpResponse,
  HttpErrorResponse,
} from '@angular/common/http';
import { Observable, finalize, tap } from 'rxjs';
import { MessageService } from 'primeng/api';

@Injectable()
export class BadRequestInterceptor implements HttpInterceptor {
  constructor(private messageService: MessageService) {}

  intercept(
    request: HttpRequest<unknown>,
    next: HttpHandler
  ): Observable<HttpEvent<unknown>> {
    return next.handle(request).pipe(
      tap({
        error: (error) => {
          if (!(error instanceof HttpErrorResponse && error.status === 422)) {
            return;
          }

          const message: string = error.error['message'];

          this.messageService.add({
            key: 'toasts',
            severity: 'error',
            summary: message,
          });
        },
      })
    );
  }
}
