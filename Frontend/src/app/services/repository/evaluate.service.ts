import { HttpClient, HttpResponse } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { CredentialService } from '../credential.service';
import { environment } from '../../environments/environment';
import { Observable, catchError } from 'rxjs';
import { ICreateEvaluation } from 'src/models/create/create-evaluation';

@Injectable({
  providedIn: 'root'
})
export class EvaluateService {
  readonly #clientHeaders: { [header: string]: string };
  #evaluateUrl: string = `${environment.apiUrl}/evaluation`;

  constructor(private http: HttpClient, private credentials: CredentialService) {
    this.#clientHeaders = {
      Authorization: `Bearer ${this.credentials.token}`,
      Accept: 'application/json',
      'Content-Type': 'application/json',
    };
  }

  evaluateResponsibility(entity: ICreateEvaluation): Observable<HttpResponse<Object>> {
    return this.http.post(this.#evaluateUrl, entity, {
      headers: this.#clientHeaders,
      observe: 'response'
    });
  }

  // cancelEvaluation(data: any): Observable<any> {
    
  // }

}
