import {HttpClient, HttpResponse} from '@angular/common/http';
import {Injectable} from '@angular/core';
import {Observable} from 'rxjs';
import {IAssignment} from '../../../models/assignment.model';
import {IAssignationRanking} from '../../../models/create/create-assignation-ranking';
import {IRemoveAssignRanking} from '../../../models/delete/remove-assign-ranking';
import {environment} from '../../environments/environment';
import {CredentialService} from '../credential.service';
import {ICreateAssignment} from 'src/models/create/create-assignment';
import {IUpdateAssignment} from 'src/models/update/update-assignment';

@Injectable({
  providedIn: 'root',
})
export class AssignmentService {
  constructor(private http: HttpClient, private credentials: CredentialService) {
    this.#clientHeaders = {
      Authorization: `Bearer ${this.credentials.token}`,
      Accept: 'application/json',
      'Content-Type': 'application/json',
    };
  }

  readonly #clientHeaders: { [header: string]: string };
  readonly #assignmentUrl: string = `${environment.apiUrl}/assignment`;

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

  create(entity: ICreateAssignment): Observable<HttpResponse<Object>> {
    return this.http.post(this.#assignmentUrl, entity, {
      headers: this.#clientHeaders,
      observe: 'response',
    });
  }

  update(id: number, entity: IUpdateAssignment): Observable<HttpResponse<Object>> {
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

  createdByTeacher(id: number): Observable<IAssignment[]> {
    const url: string = `${this.#assignmentUrl}/creator/${id}`;
    return this.http.get<IAssignment[]>(url, {
      headers: this.#clientHeaders,
      observe: 'body'
    });
  }

  assignToRank(entity: IAssignationRanking): Observable<any> {
    const url: string = `${this.#assignmentUrl}/assign/ranking/${entity.url_rankCode}`;
    return this.http.post(url, entity, {
      headers: this.#clientHeaders,
    });
  }

  removeFromRank(entity: IRemoveAssignRanking): Observable<HttpResponse<Object>> {
    const url: string = `${this.#assignmentUrl}/${entity.url_assignmentId}/remove/ranking/${entity.url_rankCode}`;
    return this.http.get(url, {
      headers: this.#clientHeaders,
      observe: 'response'
    });
  }
}
