import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EvaluationHistoryComponent } from './evaluation-history.component';

describe('EvaluationHistoryComponent', () => {
  let component: EvaluationHistoryComponent;
  let fixture: ComponentFixture<EvaluationHistoryComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ EvaluationHistoryComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(EvaluationHistoryComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
