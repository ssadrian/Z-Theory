import { Component, OnInit } from '@angular/core';
import { CredentialService } from '../../../services/credential.service';
import { IStudent } from '../../../../models/student.model';
import { StudentService } from 'src/app/services/repository/student.service';
import { FormBuilder, Validators } from '@angular/forms';
import { Base64Service } from 'src/app/services/base64.service';
import { IUpdateStudent } from 'src/models/update/update-student';
import { Router } from '@angular/router';
import {IRanking} from '../../../../models/ranking.model';
import {RankingService} from '../../../services/repository/ranking.service';

@Component({
  selector: 'app-student-profile',
  templateUrl: './student-profile.component.html',
  styleUrls: ['./student-profile.component.scss'],
})
export class StudentProfileComponent {
  constructor(
    private credentials: CredentialService,
    private studentService: StudentService,
    private fb: FormBuilder,
    private b64: Base64Service
  ) {}

  show: boolean = false;

  student: IStudent = this.credentials.currentUser as IStudent;
  rankings: IRanking[] = [];

  form = this.fb.group({
    nickname: ['', [Validators.required]],
    name: ['', [Validators.required]],
    surnames: ['', [Validators.required]],
    password: ['', [Validators.required]],
    password_confirmation: ['', [Validators.required]],
    email: ['', [Validators.required, Validators.email]],
    tos: [false, [Validators.requiredTrue]],
    birth_date: [''],
    center: [''],
  });

  #b64Avatar: string = '';

  ngOnInit(): void {
  this.rankingRepository.all()
    .subscribe((rankings: IRanking[]): void => {
      this.rankings = rankings.filter((r: IRanking): boolean => r.student_id === this.student.id);
    });

  encodeAvatar(event: Event): void {
    this.b64.toBase64(event).then((b64: string): void => {
      this.#b64Avatar = b64;
    });
  }

  updateAvatar() {
    let student: IUpdateStudent = {
      avatar: this.#b64Avatar,
      name: this.student.name!,
      surnames: this.student.surnames!,
      nickname: this.student.nickname!,
      birth_date: this.student.birth_date.toString()!,
    };

    this.studentService
      .update(this.student.id, student)
      .subscribe((response) => {});
    this.student.avatar = this.#b64Avatar;

    this.show = false;
  }

  toggle() {
    this.show = true;
  }
}
