```http request
###
#@name Register a new student
#@no-log
POST http://localhost:8080/api/register/student
Content-Type: application/json
Accept: application/json

{
  "name": "{{$random.alphabetic(10)}}",
  "surnames": "{{$random.alphabetic(5)}} {{$random.alphabetic(5)}}",
  "nickname": "{{$random.alphabetic(10)}}",
  "email": "{{$random.alphabetic(5)}}@{{$random.alphabetic(5)}}.com",
  "password": "12345678",
  "password_confirmation": "12345678",
  "birth_date": "2023-02-01"
}


###
#@name Register a new teacher
#@no-log
POST http://localhost:8080/api/register/teacher
Content-Type: application/json
Accept: application/json

{
  "name": "{{$random.alphabetic(10)}}",
  "surnames": "{{$random.alphabetic(5)}} {{$random.alphabetic(5)}}",
  "nickname": "{{$random.alphabetic(10)}}",
  "email": "{{$random.alphabetic(5)}}@{{$random.alphabetic(5)}}.com",
  "password": "12345678",
  "password_confirmation": "12345678",
  "center": "{{$random.alphabetic(5)}}"
}


###
#@name Get all students
#@no-log
GET http://localhost:8080/api/student/all
Accept: application/json


###
#@name Get a specific student
#@no-log
GET http://localhost:8080/api/student
Accept: application/json
Content-Type: application/json

{
  "id": 3
}


###
#@name Create a student
#@no-log
POST http://localhost:8080/api/student
Accept: application/json
Content-Type: application/json

{
  "nickname": "{{$random.alphabetic(10)}}",
  "name": "{{$random.alphabetic(10)}}",
  "surnames": "{{$random.alphabetic(10)}}",
  "email": "{{$random.alphabetic(5)}}@{{$random.alphabetic(5)}}.com",
  "password": "12345678",
  "password_confirmation": "12345678",
  "birth_date": "2023-02-02"
}


###
#@name Update a student
#@no-log
PUT http://localhost:8080/api/student
Content-Type: application/json

{
  "id": 3,
  "nickname": "{{$random.alphabetic(10)}}",
  "name": "{{$random.alphabetic(10)}}",
  "surnames": "{{$random.alphabetic(10)}}",
  "email": "{{$random.alphabetic(5)}}@{{$random.alphabetic(5)}}.com",
  "birth_date": "2020-02-02"
}


###
#@name Delete a student
#@no-log
DELETE http://localhost:8080/api/student
Accept: application/json
Content-Type: application/json

{
  "id": 3
}


###
#@name Get all teachers
#@no-log
GET http://localhost:8080/api/teacher/all
Accept: application/json


###
#@name Get a specific teacher
#@no-log
GET http://localhost:8080/api/teacher
Accept: application/json
Content-Type: application/json

{
  "id": 1
}


###
#@name Create a teacher
#@no-log
POST http://localhost:8080/api/teacher
Accept: application/json
Content-Type: application/json

{
  "nickname": "{{$random.alphabetic(10)}}",
  "name": "{{$random.alphabetic(10)}}",
  "surnames": "{{$random.alphabetic(10)}}",
  "email": "{{$random.alphabetic(5)}}@{{$random.alphabetic(5)}}.com",
  "password": "12345678",
  "password_confirmation": "12345678",
  "center": "{{$random.alphabetic(5)}}"
}


###
#@name Update a teacher
#@no-log
PUT http://localhost:8080/api/teacher
Content-Type: application/json

{
  "id": 1,
  "nickname": "{{$random.alphabetic(10)}}",
  "name": "{{$random.alphabetic(10)}}",
  "surnames": "{{$random.alphabetic(10)}}",
  "email": "{{$random.alphabetic(5)}}@{{$random.alphabetic(5)}}.com",
  "center": "Works"
}


###
#@name Delete a teacher
#@no-log
DELETE http://localhost:8080/api/teacher
Accept: application/json
Content-Type: application/json

{
  "id": 1
}
```
