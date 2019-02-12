<?php

namespace App\Security;

use Symfony\Component\Security\Core\Authentication\RememberMe\TokenProviderInterface;
use Symfony\Component\Security\Core\Authentication\RememberMe\PersistentTokenInterface;
use Symfony\Component\Security\Core\Authentication\RememberMe\PersistentToken;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Predis\Client;


class RedisTokenProvider implements TokenProviderInterface
{
    private $conn;

    public function __construct(Client $conn)
    {
        $this->conn = $conn;
    }

    /**
     * {@inheritdoc}
     */
    public function loadTokenBySeries($series)
    {
        $row = $this->conn->hgetall($series);
        if ($row) {
            return new PersistentToken($row['class'], $row['username'], $series, $row['value'], new \DateTime($row['lastUsed']));
        }

        throw new TokenNotFoundException('No token found.');
    }

    /**
     * {@inheritdoc}
     */
    public function deleteTokenBySeries($series)
    {
        $this->conn->del($series);
    }

    /**
     * {@inheritdoc}
     */
    public function updateToken($series, $tokenValue, \DateTime $lastUsed)
    {
        $paramValues = array(
            'value' => $tokenValue,
            'lastUsed' => $lastUsed->format('Y-m-d H:i:s'),
            'series' => $series,
        );
        if (!$this->conn->hMset($series,$paramValues)) {
            throw new TokenNotFoundException('No token found.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createNewToken(PersistentTokenInterface $token)
    {
        $paramValues = array(
            'class' => $token->getClass(),
            'username' => $token->getUsername(),
            'value' => $token->getTokenValue(),
            'lastUsed' => $token->getLastUsed()->format('Y-m-d H:i:s'),
        );

        $this->conn->hMset($token->getSeries(),$paramValues);
    }
}
