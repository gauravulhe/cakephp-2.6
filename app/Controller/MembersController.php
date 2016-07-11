<?php
App::uses('AppController', 'Controller');
/**
 * Members Controller
 *
 * @property Member $Member
 * @property PaginatorComponent $Paginator
 */
class MembersController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');


/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Member->recursive = 0;
		$this->set('members', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Member->exists($id)) {
			throw new NotFoundException(__('Invalid member'));
		}
		$options = array('conditions' => array('Member.' . $this->Member->primaryKey => $id));
		$this->set('member', $this->Member->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			//debug($this->request);exit;
			$fileName = rand(000000, 999999).'_'.$this->request->data['Member']['avatar']['name'];
			//debug($fileName);
			$tmpName = $this->request->data['Member']['avatar']['tmp_name'];
			//debug($tmpName);exit;
			$uploadPath = WWW_ROOT.'uploads/';
			$uploadFile = $uploadPath.$fileName;
			//debug($uploadFile);exit;

			// move the image

			if(move_uploaded_file($tmpName, $uploadFile)){
				$this->Member->create();
				$this->request->data['Member']['avatar'] = $fileName;
				//debug($this->request->data['Member']['avatar']['name']);exit;
				if ($this->Member->save($this->request->data)) {
					$this->Session->setFlash(__('The member has been saved.'));
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('The member could not be saved. Please, try again.'));
				}

			}
		}

		// passing options for gender field
		$gender = array(
			'Male' => 'Male',
			'Female' => 'Female'
		);
		$this->set('genders', $gender);

		// passing options for education field
		$education = array(
			'10TH' => '10TH', 
			'12TH' => '12TH',
			'Graducation' => 'Graducation',
			'Post Graducation' => 'Post Graducation'
		);
		$this->set('educations', $education);
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Member->exists($id)) {
			throw new NotFoundException(__('Invalid member'));
		}
		if ($this->request->is(array('post', 'put'))) {

			debug($this->request->data);
			if (!empty($this->request->data['Member']['avatar']['name'])) {
				$fileName = rand(000000, 999999).'_'.$this->request->data['Member']['avatar']['name'];
				//debug($fileName);
				$tmpName = $this->request->data['Member']['avatar']['tmp_name'];
				//debug($tmpName);exit;
				$uploadPath = WWW_ROOT.'uploads/';
				$uploadFile = $uploadPath.$fileName;
				//debug($uploadFile);exit;

				// move the image

				if(move_uploaded_file($tmpName, $uploadFile)){
					$this->Member->create();
					$this->request->data['Member']['avatar'] = $fileName;
					//debug($this->request->data['Member']['avatar']['name']);exit;
					if ($this->Member->save($this->request->data)) {
						$this->Session->setFlash(__('The member has been saved.'));
						return $this->redirect(array('action' => 'index'));
					} else {
						$this->Session->setFlash(__('The member could not be saved. Please, try again.'));
					}

				}
			}
			else{
					$this->Member->create();
					$this->request->data['Member']['avatar'] = $this->request->data['Member']['avatar1'];
					//debug($this->request->data['Member']['avatar']['name']);exit;
					if ($this->Member->save($this->request->data)) {
						$this->Session->setFlash(__('The member has been saved.'));
						return $this->redirect(array('action' => 'index'));
					} else {
						$this->Session->setFlash(__('The member could not be saved. Please, try again.'));
					}	
			}
			if ($this->Member->save($this->request->data)) {
				$this->Session->setFlash(__('The member has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The member could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Member.' . $this->Member->primaryKey => $id));
			$this->request->data = $this->Member->find('first', $options);


			$avatar = $this->request->data['Member']['avatar'];
			//debug($this->request->data);exit;
			$this->set('avatar', $avatar);

			// passing options for gender field
			$gender_data = $this->request->data['Member']['gender'];
			$gender = array(
				'Male' => 'Male',
				'Female' => 'Female'
			);
			$atts = array(
				'legend' => false,
				'checked' => $gender_data				
			);
			$this->set('genders', $gender);

			// passing options for education field
			$edu_data = $this->request->data['Member']['education'];
			$education = array(
				'10TH' => '10TH', 
				'12TH' => '12TH',
				'Graducation' => 'Graducation',
				'Post Graducation' => 'Post Graducation',
				'selected' => $edu_data
			);
			$this->set('educations', $education);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Member->id = $id;
		if (!$this->Member->exists()) {
			throw new NotFoundException(__('Invalid member'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Member->delete()) {
			$this->Session->setFlash(__('The member has been deleted.'));
		} else {
			$this->Session->setFlash(__('The member could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
