<?php
namespace MainBundle\Security\Core\User;

use FOS\UserBundle\Model\UserManagerInterface;
use HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\GoogleResourceOwner;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\User\UserChecker;
use Symfony\Component\HttpFoundation\RequestStack;

class FOSUBUserProvider extends BaseClass
{

    protected $requestStack;

    public function __construct(UserManagerInterface $userManager, array $properties, RequestStack $requestStack)
    {
        parent::__construct($userManager, $properties);
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        //parent::connect($user, $response);
        $property = $this->getProperty($response);
        $username = $response->getUsername();
        $usermail = $response->getResponse()['email'];
        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();

        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';

        //we "disconnect" previously connected users
        //if (null !== $previousUser = $this->userManager->findUserByUsernameOrEmail(array($property => $usermail))) {
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }

        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());

        $this->userManager->updateUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {

        $service = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($service);
        $setter_id = $setter . 'Id';
        $setter_token = $setter . 'AccessToken';
        $myVarUsername = '';
        if ($service === 'google') {
            $myVarUsername = $this->specialCharacter($response->getResponse()['family_name']);
        }elseif ($service === 'facebook'){
            $myVarUsername = $this->specialCharacter($response->getResponse()['first_name']);
        }

        //$username = $response->getUsername();
        /** @var Users $user */
        $socialID = $response->getUsername();
        $user = $this->userManager->findUserBy(
            array(
                $this->getProperty($response) => $socialID
            )
        );
        if (null === $user) {
            $user = $this->userManager->findUserBy(
                array(
                    'username' => $myVarUsername
                )
            );
        }

        if (null === $user) {
            $user = $this->userManager->findUserByEmail($response->getEmail());
        }

        if (null === $user) {
            $user = $this->userManager->createUser();
            $user->$setter_id( $response->getUsername());
            $user->$setter_token($response->getAccessToken());
            $user->setIpUser($this->requestStack->getCurrentRequest()->getClientIp());
            switch ($service) {
                case 'google':
                    $myVarUsername = $this->specialCharacter($response->getResponse()['family_name']);
                    $user->setUsername($myVarUsername);
                    $user->setFirstname($response->getResponse()['family_name']);
                    $user->setLastname($response->getResponse()['given_name']);
                    $user->setEmail($response->getResponse()['email']);
                    $user->setPlainPassword($myVarUsername);
                    $user->setGoogleEmail($response->getResponse()['email']);
                    $user->setGoogleVerifiedEmail($response->getResponse()['verified_email']);
                    $user->setGoogleLink($response->getResponse()['link']);
                    $user->setGooglePicture($response->getResponse()['picture']);
                    $user->setGoogleGender($response->getResponse()['gender']);
                    $user->setGoogleLocale($response->getResponse()['locale']);
                    break;
                case 'facebook':
                    $myVarUsername = $this->specialCharacter($response->getResponse()['first_name']);
                    $user->setUsername($myVarUsername);
                    $user->setFirstname($response->getResponse()['first_name']);
                    $user->setLastname($response->getResponse()['last_name']);
                    $user->setEmail($response->getResponse()['email']);
                    $user->setPlainPassword($myVarUsername);
                    break;
            }

            /*
             * Envoyer un email avec les paramètres de connexion
             */
            $user->setEnabled(true);
            $this->userManager->updateUser($user);
            return $user;
        }
        //$user = parent::loadUserByOAuthUserResponse($response);

        $user->$setter_id( $response->getUsername());
        $user->$setter_token($response->getAccessToken());

        //$this->userManager->updateUser($user);

        return $user;
    }

    function specialCharacter($chaine){

        //  les accents
        $chaine=trim($chaine);
        $chaine= strtr($chaine,"ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ","aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn");

        //  les caracètres spéciaux (aures que lettres et chiffres en fait)
        $chaine = preg_replace('/([^.a-z0-9]+)/i', '-', $chaine);
        $chaine = strtolower($chaine);

        return $chaine;

    }

    /*public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {

        $username = $response->getUsername();
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));
        //when the user is registrating
        if (null === $user) {
            $service = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($service);
            $setter_id = $setter.'Id';
            $setter_token = $setter.'AccessToken';
            // create new user here
            $user = $this->userManager->createUser();
            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());
            //I have set all requested data with the user's username
            //modify here with relevant data
            switch ($service) {
                case 'google':
                    $user->setUsername($username);
                    $user->setFirstname($response->getResponse()['family_name']);
                    $user->setLastname($response->getResponse()['given_name']);
                    $user->setEmail($response->getResponse()['email']);
                    $user->setPlainPassword($username);
                    break;
                case 'facebook':
                    $user->setUsername($username);
                    $user->setFirstname($response->getResponse()['first_name']);
                    $user->setLastname($response->getResponse()['last_name']);
                    $user->setEmail($response->getResponse()['email']);
                    $user->setPlainPassword($username);
                    break;
            }

            $user->setEnabled(true);
            $this->userManager->updateUser($user);
            return $user;
        }

        //if user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);

        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';

        //update access token
        $user->$setter($response->getAccessToken());

        return $user;
    }*/

}