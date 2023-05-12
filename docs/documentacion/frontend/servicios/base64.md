import {Injectable} from "@angular/core";

@Injectable({
  providedIn: "root",
})
export class Base64Service {
  async toBase64(event: any): Promise<string> {
    const files: FileList | null = event.files;

    if (!files) {
      return "";
    }

    return await this.#readBase64(files.item(0))
      .then((data: string): string => {
        return data;
      });
  }

  async #readBase64(file: any): Promise<any> {
    const reader: FileReader = new FileReader();

    return new Promise((resolve, reject): void => {
      reader.addEventListener("load", (): void => {
        resolve(reader.result?.toString() ?? "");
      }, false);
      reader.addEventListener("error", (event: ProgressEvent<FileReader>): void => {
        reject(event);
      }, false);

      reader.readAsDataURL(file);
    });
  }
}
