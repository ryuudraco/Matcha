<?php 

namespace Src\Services;
use Slim\Http\Request;
use Slim\Http\Response;
use Src\DAO\UserDAO;
use Src\Utils\Validator;

class EditProfileService extends Service {

    public function handlePost() {

        $fields = $this->input([
            'username',
            'email',
            'first_name',
            'last_name',
            'age',
            'gender_id',
            'biography',
            'interests',
            'preference',
            'city',
            'province',
            'country',
            'postal_code'
        ]);

        $uploaded_file = $this->request->getUploadedFiles();

        /**
         * Remove everything that is empty - we don't want to delete data
         */
        $fields = array_filter($fields, function($val) {
            return isset($val);
        });
        
        /**
         * Validation for post - need to add validation for other fields as well
         */
        $validation_result = Validator::validate($fields, [
			'username' => 'required|string|username_unique',
			'email' => 'required',
			'first_name' => 'required|string',
			'last_name' => 'required|string'
        ]);
        
		if ($validation_result !== true) {
            //if updating your own profile it will complain about username being taken. Needs fixing
			//return $this->render('profile.html', ['validation_errors' => $validation_result]);
        }

        /**
         * Handle image upload
         */

        $img = $uploaded_file['avatar'];
        $directory = $_SERVER['DOCUMENT_ROOT'] . '/uploads';

        //if no errors then we push avatar image location to array, which then gets processed by DAO
        if($img->getError() === UPLOAD_ERR_OK) {
            $filename = self::moveUploadedFile($directory, $img);
            $fields['avatar_image'] = 'uploads/' . $filename;
        }

        $update_state = UserDAO::update($fields);
        $user = UserDAO::fetch([$_SESSION['user_id']], 'ID');

        return $this->render('profile.html', ['update_success' => $update_state, 'user' => $user]);

    }

        function moveUploadedFile($directory, $uploadedFile)
        {
            $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
            $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
            $filename = sprintf('%s.%0.8s', $basename, $extension);

            $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
            return $filename;
        }

}