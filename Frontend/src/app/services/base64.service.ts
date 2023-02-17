import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class Base64Service {
  setToBase64(event: Event, target: any): void {
    const files: FileList | null = (event.target as HTMLInputElement).files;

    if (!files) {
      return;
    }

    this.#readBase64(files.item(0))
      .then((data: string): void => {
        target = data;
      });
  }

  #readBase64(file: any): Promise<string> {
    const reader: FileReader = new FileReader();

    return new Promise((resolve, reject): void => {
      reader.addEventListener('load', (): void => {
        resolve(reader.result?.toString() ?? '');
      }, false);
      reader.addEventListener('error', (event: ProgressEvent<FileReader>): void => {
        reject(event);
      }, false);

      reader.readAsDataURL(file);
    });
  }
}
