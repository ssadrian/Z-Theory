import { HttpClient, HttpResponse } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { CredentialService } from '../credential.service';
import { environment } from '../../environments/environment';
import { Observable, catchError } from 'rxjs';
import { ICreateEvaluation } from 'src/models/create/create-evaluation';
import { IDeleteEvaluation } from 'src/models/delete/delete-evaluation';

@Injectable({
  providedIn: 'root',
})
export class EvaluateService {
  constructor(
    private http: HttpClient,
    private credentials: CredentialService
  ) {
    this.#clientHeaders = {
      Authorization: `Bearer ${this.credentials.token}`,
      Accept: 'application/json',
      'Content-Type': 'application/json',
    };
  }

  readonly #clientHeaders: { [header: string]: string };
  readonly #evaluateUrl: string = `${environment.apiUrl}/evaluation`;

  create(entity: ICreateEvaluation): Observable<HttpResponse<Object>> {
    return this.http.post(this.#evaluateUrl, entity, {
      headers: this.#clientHeaders,
      observe: 'response',
    });
  }

  delete(entity: IDeleteEvaluation): Observable<HttpResponse<Object>> {
    return this.http.delete(this.#evaluateUrl, {
      headers: this.#clientHeaders,
      observe: 'response',
      body: entity,
    });
  }
}
