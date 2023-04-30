import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EvaluationHistoryListComponent } from './evaluation-history-list.component';

describe('EvaluationHistoryListComponent', () => {
  let component: EvaluationHistoryListComponent;
  let fixture: ComponentFixture<EvaluationHistoryListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ EvaluationHistoryListComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(EvaluationHistoryListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
