import { Component, Input, OnInit } from '@angular/core';
import { MessageService } from 'primeng/api';
import { MiscService } from 'src/app/services/misc.service';
import { EvaluationService } from 'src/app/services/repository/evaluation.service';
import { IDeleteEvaluation } from 'src/models/delete/delete-evaluation';
import { IEvaluationHistory } from 'src/models/evaluation-history.model';

@Component({
  selector: 'evaluation-history-list',
  templateUrl: './evaluation-history-list.component.html',
  styleUrls: ['./evaluation-history-list.component.scss'],
})
export class EvaluationHistoryListComponent implements OnInit {
  @Input() target: number = 0;

  constructor(
    private messageService: MessageService,
    private evaluationService: EvaluationService,
    private miscService: MiscService
  ) {}

  evaluationHistory: IEvaluationHistory[] = [];

  ngOnInit(): void {
    this.evaluationService.forTeacher(this.target).subscribe((res) => {
      this.evaluationHistory = res;
    });
  }

  delete(event: Event, id: number) {
    const onAccept = () => {
      const entity: IDeleteEvaluation = {
        id: id,
      };

      this.evaluationService.delete(entity).subscribe((res) => {
        this.evaluationHistory = this.evaluationHistory.filter(
          (x) => x.id !== id
        );
        this.messageService.add({
          key: 'toasts',
          severity: 'success',
          detail: 'Historial eliminado',
        });
      });
    };

    this.miscService.confirmAction(
      'Estas seguro que quieres eliminar este historial?',
      event.target,
      onAccept
    );
  }
}
