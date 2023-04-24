import { Component, Input, OnInit } from '@angular/core';

@Component({
  selector: 'evaluation-history-list',
  templateUrl: './evaluation-history-list.component.html',
  styleUrls: ['./evaluation-history-list.component.scss']
})
export class EvaluationHistoryListComponent implements OnInit {
  @Input() target: number = 0;

  evaluationHistory: IEvaluationHistory[] = [];

  ngOnInit(): void {
  }
}
