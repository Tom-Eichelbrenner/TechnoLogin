<?php

namespace App\Model;

class WelcomeModel
{
    private ?string $siteName = null;
    private ?string $userName = null;
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