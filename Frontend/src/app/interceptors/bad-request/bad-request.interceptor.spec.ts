import { TestBed } from '@angular/core/testing';

import { BadRequestInterceptor } from './bad-request.interceptor';

describe('UnauthorizedInterceptor', () => {
  beforeEach(() => TestBed.configureTestingModule({
    providers: [
      BadRequestInterceptor
      ]
  }));

  it('should be created', () => {
    const interceptor: BadRequestInterceptor = TestBed.inject(BadRequestInterceptor);
    expect(interceptor).toBeTruthy();
  });
});
