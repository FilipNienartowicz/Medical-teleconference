using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Data.Entity;
using System.Linq;
using System.Web;
using WebMatrix.WebData;

namespace Medical_teleconference.Models
{
    [Table("Users")]
    public class User
    {
        [Key]
        [DatabaseGeneratedAttribute(DatabaseGeneratedOption.Identity)]
        public int UserId { get; set; }

        [Required(ErrorMessage = "Nazwa użytkownika jest wymagana!")]
        [Display(Name = "Nazwa użytkownika")]
        [StringLength(30, ErrorMessage = "Nazwa użytkownika może się składać maksymalnie z 30 znaków!")]
        public string UserName { get; set; }
        
        public ICollection<Room> Rooms { get; set; }

        public User()
        {
            Rooms = new HashSet<Room>();
        }

         public static bool IsLoggedIn()
        {
             if((System.Web.HttpContext.Current.User != null) && (System.Web.HttpContext.Current.User.Identity.IsAuthenticated))
             {
                 if (WebSecurity.CurrentUserId >= 0)
                     return true;
                 WebSecurity.Logout();
             }
             return false;
        }
    }
}