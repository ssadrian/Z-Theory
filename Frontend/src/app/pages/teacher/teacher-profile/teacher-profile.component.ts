import { Component } from '@angular/core';
import { Validators, AbstractControl, FormBuilder } from '@angular/forms';
import { RankingService } from 'src/app/services/repository/ranking.service';
import { ITeacher } from '../../../../models/teacher.model';
import { CredentialService } from '../../../services/credential.service';
import { v4 as uuidv4 } from 'uuid';

@Component({
  selector: 'app-teacher-profile',
  templateUrl: './teacher-profile.component.html',
  styleUrls: ['./teacher-profile.component.scss'],
})
export class TeacherProfileComponent {
  constructor(
    private credentials: CredentialService,
    private fb: FormBuilder,
    private rankingService: RankingService
  ) {}

  teacher: ITeacher = this.credentials.currentUser as ITeacher;
  isSubmit: boolean = false;

  createRankingForm = this.fb.group({
    name: ['', [Validators.required]],
  });

  get formControl(): { [key: string]: AbstractControl } {
    return this.createRankingForm.controls;
  }

  submit(): void {
    this.isSubmit = true;

    const formValue = this.createRankingForm.value;
    this.rankingService
      .create({
        name: formValue.name!,
        creator: this.teacher.id,
        code: uuidv4(),
      })
      .subscribe();
  }
}
