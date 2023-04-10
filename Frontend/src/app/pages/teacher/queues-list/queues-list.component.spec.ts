import { ComponentFixture, TestBed } from '@angular/core/testing';

import { QueuesListComponent } from './queues-list.component';

describe('QueuesListComponent', () => {
  let component: QueuesListComponent;
  let fixture: ComponentFixture<QueuesListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ QueuesListComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(QueuesListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
