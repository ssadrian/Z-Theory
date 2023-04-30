export const environment = {
  production: false,

  /**
   * 0x1 - student's special pass
   * 0x11 - teacher's special pass
   * anything else - no special pass, valid credentials must be provided
   */
  // TODO: Remove on last coding task
  studentTeacherPass: 0x11,

  apiUrl: 'http://localhost:8000/api',
};

export function teacherPass(): boolean {
  const result = checkPass(0x11);
  console.log(`Checking teacher pass ${result}`);
  return result;
}

export function studentPass(): boolean {
  const result = checkPass(0x1);
  console.log(`Checking student pass ${result}`);
  return result;
}

function checkPass(nr: number): boolean {
  return environment.studentTeacherPass === nr;
}
