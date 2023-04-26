import { Component, Input, OnInit } from '@angular/core';
import { EvaluationService } from 'src/app/services/repository/evaluation.service';
import { IEvaluationHistory } from 'src/models/evaluation-history.model';

@Component({
  selector: 'evaluation-history-list',
  templateUrl: './evaluation-history-list.component.html',
  styleUrls: ['./evaluation-history-list.component.scss'],
})
export class EvaluationHistoryListComponent implements OnInit {
  @Input() target: number = 0;

  constructor(private evaluationService: EvaluationService) {}

  evaluationHistory: IEvaluationHistory[] = [];

  ngOnInit(): void {
    this.evaluationService
      .forTeacher(this.target)
      .subscribe(res => {
        this.evaluationHistory = res;
      });
  }
}
