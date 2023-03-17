import {HttpClient, HttpResponse} from '@angular/common/http';
import {Injectable} from '@angular/core';
import {Observable} from 'rxjs';
import {ICreateRanking} from '../../../models/create/create-ranking';
import {IAssignment} from '../../../models/assignment.model';
import {IUpdateRanking} from '../../../models/update/update-ranking';
import {environment} from '../../environments/environment';
import {CredentialService} from '../credential.service';

@Injectable({
  providedIn: 'root',
})
export class AssignmentService {
  #assignmentUrl: string = `${environment.apiUrl}/assignment`;
  readonly #clientHeaders: { [header: string]: string };

  constructor(private http: HttpClient, private credentials: CredentialService) {
    this.#clientHeaders = {
      Authorization: `Bearer ${this.credentials.token}`,
      Accept: 'application/json',
      'Content-Type': 'application/json',
    };
  }

  all(): Observable<IAssignment[]> {
    return this.http.get<IAssignment[]>(this.#assignmentUrl, {
      headers: this.#clientHeaders,
    });
  }

  get(id: number): Observable<IAssignment | null> {
    const url: string = `${this.#assignmentUrl}/${id}`;
    return this.http.get<IAssignment>(url, {
      headers: this.#clientHeaders,
    });
  }

  create(entity: ICreateRanking): Observable<HttpResponse<Object>> {
    return this.http.post(this.#assignmentUrl, entity, {
      headers: this.#clientHeaders,
      observe: 'response',
    });
  }

  update(id: number, entity: IUpdateRanking): Observable<HttpResponse<Object>> {
    const url: string = `${this.#assignmentUrl}/${id}`;
    return this.http.put(url, entity, {
      headers: this.#clientHeaders,
      observe: 'response',
    });
  }

  delete(id: number): Observable<HttpResponse<Object>> {
    const url: string = `${this.#assignmentUrl}/${id}`;
    return this.http.delete(url, {
      headers: this.#clientHeaders,
      observe: 'response',
    });
  }

  assignToRank(): Observable<any> {
    return new Observable<any>();
  }
}
