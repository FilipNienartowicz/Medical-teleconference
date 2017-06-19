using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Data.Entity;
using System.Linq;
using System.Web;

namespace Medical_teleconference.Models
{
    [Table("Users")]
    public class User : DbContext
    {
        [Key]
        [DatabaseGeneratedAttribute(DatabaseGeneratedOption.Identity)]
        public int UserId { get; set; }
        [StringLength(50, ErrorMessage = "Maximal length of the username is 50 characters!")]
        public string UserName { get; set; }
    }
}