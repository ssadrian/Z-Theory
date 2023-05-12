import { Component } from '@angular/core';
import { CredentialService } from 'src/app/services/credential.service';
import { ITeacher } from 'src/models/teacher.model';

@Component({
  selector: 'evaluation-history',
  templateUrl: './evaluation-history.component.html',
  styleUrls: ['./evaluation-history.component.scss'],
})
export class EvaluationHistoryComponent {
  constructor(private credentialService: CredentialService) {}

  teacher: ITeacher = this.credentialService.currentUser as ITeacher;
}
