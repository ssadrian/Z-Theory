import { Injectable } from '@angular/core';
import { ConfirmationService, MessageService } from 'primeng/api';

@Injectable({
  providedIn: 'root',
})
export class MiscService {
  constructor(
    private messageService: MessageService,
    private confirmationService: ConfirmationService
  ) {}

  copyText(event: string | Event): void {
    if (typeof event === 'string') {
      navigator.clipboard.writeText(event).then((): void => {});
      this.#logSuccess();
    } else if (event instanceof Event) {
      navigator.clipboard
        .writeText((<HTMLElement>event.target).innerText)
        .then((): void => {});
      this.#logSuccess();
    }
  }

  #logSuccess(): void {
    this.messageService.add({
      key: 'toasts',
      severity: 'success',
      summary: 'Copiado en clipboard',
    });
  }

  confirmAction(
    message: string,
    target: EventTarget | null,
    onAccept: Function,
    onReject: Function = (): void => {}
  ): void {
    if (target === null) {
      return;
    }

    this.confirmationService.confirm({
      target: target,
      icon: 'pi pi-exclamation-triangle',
      message: message,
      acceptLabel: 'Sí',
      rejectLabel: 'No',
      accept: () => onAccept(),
      reject: () => onReject(),
    });
  }
}
