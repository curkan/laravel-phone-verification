## Laravel SMS Verification

[![Build Status](https://travis-ci.org/joemccann/dillinger.svg?branch=master)](https://travis-ci.org/joemccann/dillinger)

Пакет Laravel для подтверждения действий по SMS (регистрация, авторизация)

## Возможности

- Отправка SMS c кодом верификации
- Подтверждение SMS по коду и телефону
- Поддержка кастомной длины кода
- Простое использование

## Установка

Для установки достаточно использовать composer и версию Laravel 8+


`composer require gogain/laravel-phone-verification`

Копируем шаблоны проекта с помощью команды:

`php artisan vendor:publish --provider="Gogain\LaravelPhoneVerification\Providers\SmsVeriviedServiceProvider"`

Обязательно настраиваем .env файл, прописываем следующее:

```
SMS_VERIFACTION_CODELENGTH=4
SMS_VERIFACTION_CODELIFETIME=60
SMS_VERIFACTION_PATH='api/v1'
SMS_SENDER='test' 
SMS_VERIFACTION_SMSAERO_EMAIL='email@example.com'
SMS_VERIFACTION_SMSAERO_APIKEY='jCsdfsfFdzmFYzKlsdffuO4'
```
**SMS_VERIFACTION_PATH** - префикс к роутам, по стандарту *api/v1*

**SMS_SENDER** может быть двух значений:
1. **test** - тестовая отправка
2. **smsaero** - отправка через сервис (https://smsaero.ru "SmsAero.ru")

### Роуты

POST `/users/sms-verification` - Пост запрос для отправки сообщения на телефон, в теле запроса должно быть поле **phone_number**
POST `/users/sms-verification/{code}/{phone_number}` - Пост запрос проверки SMS кода. Код проверяется по кешу и сравнивает занчение телефона, если телефон отличается, то будет выдавать ошибки. Если код валидный, то выдаст сообщение об успешной валидации и добавит значение телефона в кеш, для последнующей реализации регистрации или авторизации.

**SMS_VERIFACTION_CODELIFETIME** - "жизнь" кода в секундах, по истечению удаляется из кеша. По стандарту 60 секунд.
**SMS_VERIFACTION_CODELENGTH** - длина кода, по стандарту 4 символа

### Проверка верификации телефона

После того, как телефон был проверен, то в кеш заносится об этом информация. Чтобы перед регистрацией пользователя проверить, что валидация телефона прошла успешно, необходимо добавить кастомное правило к реквесту, например так:

    use Gogain\LaravelPhoneVerification\Rules\VerifiedPhone;
	class RegisterController extends BaseController
    {
        /**
         * Register api
         *
         * @return \Illuminate\Http\Response
         */
        public function register(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'phone' => ['required','unique:users', new VerifiedPhone()],
                'password' => 'required',
                'c_password' => 'required|same:password',
            ]);
       
            return $this->sendResponse($success, 'User register successfully.');
        }
На 14 строке добавляется правило из библиотеки`new VerifiedPhone()` - это правильно проверяет по кешу, был ли успешно проверен указанный номер или нет. В случае неудачи выдаст сообщение и ошибку валидации.

#### Приятного использования



