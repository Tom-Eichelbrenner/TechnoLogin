<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class WelcomeModel
{
    #[Length(min: 3, max: 50)]
    private ?string $siteName = null;
    #[Length(min: 3, max: 50)]
    private ?string $userName = null;

    #[Regex(pattern:"^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$^", message:"Votre mot de passe doit contenir au moins 8 caractères et contenir au moins un chiffre et une lettre majuscule")]
    private ?string $password = null;

    const SITE_TITLE_LABEL = 'Titre du site';
    const SITE_TITLE_NAME = 'blog_title';
    const SITE_TITLE_DEFAULT = 'Mon site';
    const SITE_TITLE_PLACEHOLDER = 'Mon site';

    const USERNAME_LABEL = 'Nom d\'utilisateur';
    const USERNAME_PLACEHOLDER = 'John Doe';

    const PASSWORD_LABEL = 'Mot de passe';
    const PASSWORD_PLACEHOLDER = '********';

    const SUBMIT_LABEL = 'Soumettre';

    const SITE_INSTALLED_LABEL = 'Site installé';
    const SITE_INSTALLED_NAME = 'blog_installed';



    /**
     * @return string|null
     */
    public function getSiteName(): ?string
    {
        return $this->siteName;
    }

    /**
     * @param string|null $siteName
     */
    public function setSiteName(?string $siteName): void
    {
        $this->siteName = $siteName;
    }

    /**
     * @return string|null
     */
    public function getUserName(): ?string
    {
        return $this->userName;
    }

    /**
     * @param string|null $userName
     */
    public function setUserName(?string $userName): void
    {
        $this->userName = $userName;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }


}