import {HttpResponse} from '@angular/common/http';
import {Observable} from 'rxjs';

export interface IRepository<TModel> {
  all(): Observable<TModel[]>;

  get(id: number): Observable<TModel | null>;

  create<TCreateModel>(entity: TCreateModel): Observable<HttpResponse<Object>>;

  update<TUpdateModel>(id: number, entity: TUpdateModel): Observable<HttpResponse<Object>>;

  delete(id: number): Observable<HttpResponse<Object>>;
}
