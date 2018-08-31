<?php
/**
 * CLIToken.php
 * Copyright (c) 2018 thegrumpydictator@gmail.com
 *
 * This file is part of Firefly III.
 *
 * Firefly III is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Firefly III is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Firefly III. If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace FireflyIII\Support\Binder;

use FireflyIII\Repositories\User\UserRepositoryInterface;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class CLIToken
 */
class CLIToken implements BinderInterface
{

    /**
     * @param string $value
     * @param Route  $route
     *
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public static function routeBinder(string $value, Route $route)
    {
        /** @var UserRepositoryInterface $repository */
        $repository = app(UserRepositoryInterface::class);
        /** @var Collection $users */
        $users = $repository->all();

        foreach ($users as $user) {
            $accessToken = app('preferences')->getForUser($user, 'access_token', null);
            if ($accessToken->data === $value) {
                Log::info(sprintf('Recognized user #%d (%s) from his acccess token.', $user->id, $user->email));

                return $value;
            }
        }
        throw new NotFoundHttpException;
    }
}